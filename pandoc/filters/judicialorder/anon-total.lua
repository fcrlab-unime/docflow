function Span(span)
if span.classes:includes('personaldata') and span.classes:includes('judicialorder') then
  span.content = pandoc.Str('xxxxxxxxxxxxxx')
  local value, position = span.classes:find('judicialorder')
  span.classes:remove(position)
end
return span
end