function Span(span)
if span.classes:includes('personaldata') and span.classes:includes('healthdata') then
  span.content = pandoc.Str('xxxxxx')
  local value, position = span.classes:find('healthdata')
  span.classes:remove(position)
end
return span
end