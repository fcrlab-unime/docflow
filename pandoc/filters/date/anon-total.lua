function Span(span)
if span.classes:includes('personaldata') and span.classes:includes('date') then
  if span.classes:includes('day') then
    local content_string = pandoc.utils.stringify(span.content)
    span.content = pandoc.Str('xx')
    local value, position = span.classes:find('day')
    span.classes:remove(position)
  elseif span.classes:includes('month') then
    span.content = pandoc.Str('xx')
    local value, position = span.classes:find('month')
    span.classes:remove(position)
  elseif span.classes:includes('year') then
    span.content = pandoc.Str('xxxx')
    local value, position = span.classes:find('year')
    span.classes:remove(position)
  end
  local value, position = span.classes:find('date')
  span.classes:remove(position)
end
return span
end